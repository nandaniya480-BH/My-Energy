
import { Component, OnInit } from '@angular/core';
import { ClientUserModel } from 'src/app/model/client-user.model';
import { Table } from 'primeng/table';
import { ClientUserService } from 'src/app/service/client-user.service';
import { ActivatedRoute, Router } from '@angular/router';
import { APIResponse } from 'src/app/model/api-response.model';
import { NotificationService } from 'src/app/service/notification.service';
import { ClientService } from 'src/app/service/client.service';
import { ClientModel } from 'src/app/model/client.model';
import { RoleModel } from 'src/app/model/role.model';
import { PermissionCheckService } from 'src/app/service/permission.check.service';

@Component({
  selector: 'app-client-user',
  templateUrl: './client-user.component.html',
  providers: [ClientUserService, ClientService,PermissionCheckService]
})
export class ClientUserComponent implements OnInit {
  clientUserDialog: boolean = false;

  deleteClientUserDialog: boolean = false;

  clientUsers: ClientUserModel[] = [];

  roleList: RoleModel[] = [];

  selectedRole: any = {};

  clientUser: ClientUserModel = {
    id: 0,
    full_name: '',
    client_id: 0,
    password: '',
    role_id: 0
  };

  client: ClientModel = {
    id: 0,
    full_name: '',
    address: '',
    region: '',
    teams_link: ''
  };

  selectedClientUsers: ClientUserModel[] = [];

  submitted: boolean = false;

  cols: any[] = [];

  statuses: any[] = [];

  rowsPerPageOptions = [5, 10, 20];

  module = 'Client User';

  constructor(private _clientUserService: ClientUserService,
    private _notificationService: NotificationService,
    private _clientService: ClientService,
    public _permissionCheck: PermissionCheckService,
    private _router: Router,
    private _route: ActivatedRoute,) {
    if (!this.permission('R')) {
      this._router.navigate([`client/client-dashboard`]);
    }
    this._route.params.subscribe(params => {
      this.clientUser.client_id = params['id'];
      if (!(this.clientUser.client_id > 0)) {
        this.goBack();
      }
    });
  }

  permission(crud: string) {
    return this._permissionCheck.check(this.module, crud);
  }

  goBack() {
    this._router.navigate([`client/client-dashboard/${this.client.id}`]);
  }

  ngOnInit() {
    this._clientService.get(this.clientUser.client_id).subscribe((res: APIResponse<ClientModel>) => {
      this.client = res.results;
      this.getAll();
      this.getRoleList();
    });

    this.cols = [
      { field: 'full_name', header: 'Full Name' },
      { field: 'password', header: 'Password' },
      { field: 'role_name', header: 'role_name' },
    ];
  }

  onChangeOfRole() {
    this.selectedRole = {};
    this._clientUserService.getRole(this.clientUser.role_id).subscribe((res: APIResponse<Array<RoleModel>>) => {
      this.selectedRole = res.results[0];
      for (let x of this.selectedRole.permissions || []) {
        if (x.access.includes('C')) {
          x.Add = true;
        }
        if (x.access.includes('R')) {
          x.View = true;
        }
        if (x.access.includes('U')) {
          x.Update = true;
        }
        if (x.access.includes('D')) {
          x.Delete = true;
        }
      }
    });
  }

  getAll() {
    this._clientUserService.getAll(this.clientUser.client_id).subscribe((res: any) => {
      this.clientUsers = res.results.data;
    });
  }

  getRoleList() {
    this._clientUserService.getAllRole().subscribe((res: APIResponse<Array<RoleModel>>) => {
      this.roleList = res.results;
    });
  }

  openNew() {
    this.clientUser = {
      id: 0,
      full_name: '',
      client_id: this.client.id,
      password: '',
      role_id: 0
    };
    this.submitted = false;
    this.clientUserDialog = true;
  }


  editClientUser(clientUser: ClientUserModel) {
    this._clientUserService.get(clientUser.id).subscribe((res: APIResponse<ClientUserModel>) => {
      this.clientUser = res.results;
      this.onChangeOfRole();
      this.clientUserDialog = true;
    });
  }

  deleteClientUser(clientUser: ClientUserModel) {
    this.deleteClientUserDialog = true;
    this.clientUser = { ...clientUser };
  }



  confirmDelete() {
    this.deleteClientUserDialog = false;
    this._clientUserService.delete(this.clientUser?.id as number).subscribe(res => {
      this._notificationService.success('Successfully Deleted');
      this.clientUser = {
        id: 0,
        full_name: '',
        client_id: this.client.id,
        password: '',
        role_id: 0
      };
      this.getAll();
    });
  }

  hideDialog() {
    this.clientUserDialog = false;
    this.submitted = false;
  }

  saveClientUser() {
    this.submitted = true;


    if (this.clientUser.full_name != null && this.clientUser.full_name != ''
      && this.clientUser.password != null && this.clientUser.password != '') {
      if (this.clientUser.id > 0) {
        this._clientUserService.update(this.clientUser).subscribe(res => {
          this._notificationService.success('User Updated');
          this.clientUser = {
            id: 0,
            full_name: '',
            client_id: this.client.id,
            password: '',
            role_id: 0
          };
          this.getAll();
          this.clientUserDialog = false;
        });
      } else {
        this._clientUserService.save(this.clientUser).subscribe(res => {
          this._notificationService.success('User Added');
          this.clientUser = {
            id: 0,
            full_name: '',
            client_id: this.client.id,
            password: '',
            role_id: 0
          };
          this.getAll();
          this.clientUserDialog = false;
        });
      }

    }
  }


  onGlobalFilter(table: Table, event: Event) {
    table.filterGlobal((event.target as HTMLInputElement).value, 'contains');
  }

}

