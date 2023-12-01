import { Component, OnInit } from '@angular/core';
import { Table } from 'primeng/table';
import { ActivatedRoute, Router } from '@angular/router';
import { APIResponse } from 'src/app/model/api-response.model';
import { NotificationService } from 'src/app/service/notification.service';
import { ClientService } from 'src/app/service/client.service';
import { ClientModel } from 'src/app/model/client.model';
import { ClientConsumptionPlanService } from 'src/app/service/client-consumption-plan.service';
import { ClientConsumptionPlanModel } from 'src/app/model/client-consumption-plan.model';
import { PermissionCheckService } from 'src/app/service/permission.check.service';

@Component({
  selector: 'app-client-consumption-plan',
  templateUrl: './client-consumption-plan.component.html',
  providers: [ClientConsumptionPlanService, ClientService, PermissionCheckService]
})
export class ClientConsumptionPlanComponent implements OnInit {
  clientConDialog: boolean = false;

  deleteClientConDialog: boolean = false;
  test: any;
  clientCons: ClientConsumptionPlanModel[] = [];

  clientCon: ClientConsumptionPlanModel = {
    id: 0,
    client: '',
    client_user: '',
    client_plan: '',
    consumption: 0,
    status: ''
  };

  client: ClientModel = {
    id: 0,
    full_name: '',
    address: '',
    region: '',
    teams_link: ''
  };

  selectedClientCons: ClientConsumptionPlanModel[] = [];

  submitted: boolean = false;

  cols: any[] = [];

  statuses: any[] = [];

  rowsPerPageOptions = [5, 10, 20];
  module = 'Consumption Plan';
  constructor(private _clientConsPService: ClientConsumptionPlanService,
    private _notificationService: NotificationService,
    public _permissionCheck: PermissionCheckService,
    private _clientService: ClientService,
    private _router: Router,
    private _route: ActivatedRoute,) {
    if (!this.permission('R')) {
      this._router.navigate([`client/client-dashboard`]);
    }
    this._route.params.subscribe(params => {
      this.client.id = params['id'];
      if (!(this.client.id > 0)) {
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
    this._clientService.get(this.client.id).subscribe((res: APIResponse<ClientModel>) => {
      this.client = res.results;
      this.clientCon.client = this.client.full_name;
      this.getAll();
    });
    this.cols = [
      { field: 'client', header: 'client' },
      { field: 'client_user', header: 'client_user' },
      { field: 'client_plan', header: 'client_plan' },
      { field: 'consumption', header: 'consumption' },
      { field: 'status', header: 'status' },
      { field: 'created_at', header: 'created_at' },
    ];
  }

  getAll() {
    this._clientConsPService.getAll(this.client.full_name).subscribe((res: any) => {
      this.clientCons = res.results.data
    });
  }

  openNew() {
    this.clientCon = {
      id: 0,
      client: this.client.full_name,
      client_user: '',
      client_plan: '',
      consumption: 0,
      status: ''
    };
    this.submitted = false;
    this.clientConDialog = true;
  }


  edit(data: ClientConsumptionPlanModel) {
    this._clientConsPService.get(data.id).subscribe((res: APIResponse<ClientConsumptionPlanModel>) => {
      this.clientCon = res.results;
      const d = res.results.created_at || new Date();
      this.clientCon.created_at = new Date(d);
      this.clientConDialog = true;
    });
  }

  delete(data: ClientConsumptionPlanModel) {
    this.deleteClientConDialog = true;
    this.clientCon = { ...data };
  }



  confirmDelete() {
    this.deleteClientConDialog = false;
    this._clientConsPService.delete(this.clientCon.id as number).subscribe(res => {
      this._notificationService.success('Successfully Deleted');
      this.clientCon = {
        id: 0,
        client: this.client.full_name,
        client_user: '',
        client_plan: '',
        consumption: 0,
        status: ''
      };
      this.getAll();
    });
  }

  hideDialog() {
    this.clientConDialog = false;
    this.submitted = false;
  }

  save() {
    this.submitted = true;


    if (this.clientCon.client == null || this.clientCon.client == '') {
      return;
    }
    if (this.clientCon.client_plan == null || this.clientCon.client_plan == '') {
      return;
    }
    if (this.clientCon.client_user == null || this.clientCon.client_user == '') {
      return;
    }
    if (this.clientCon.created_at == null || this.clientCon.created_at == undefined) {
      return;
    }
    if (this.clientCon.status == null || this.clientCon.status == '') {
      return;
    }

    if (this.clientCon.id > 0) {
      this._clientConsPService.update(this.clientCon).subscribe(res => {
        this._notificationService.success('Updated');
        this.clientCon = {
          id: 0,
          client: this.client.full_name,
          client_user: '',
          client_plan: '',
          consumption: 0,
          status: ''
        };
        this.getAll();
        this.clientConDialog = false;
      });
    } else {
      this._clientConsPService.save(this.clientCon).subscribe(res => {
        this._notificationService.success('Added');
        this.clientCon = {
          id: 0,
          client: this.client.full_name,
          client_user: '',
          client_plan: '',
          consumption: 0,
          status: ''
        };
        this.getAll();
        this.clientConDialog = false;
      });
    }
  }


  onGlobalFilter(table: Table, event: Event) {
    table.filterGlobal((event.target as HTMLInputElement).value, 'contains');
  }

}


