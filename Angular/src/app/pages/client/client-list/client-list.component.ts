import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { APIResponse } from 'src/app/model/api-response.model';
import { ClientModel } from 'src/app/model/client.model';
import { NotificationService } from 'src/app/service/notification.service';
import { ClientService } from 'src/app/service/client.service';
import { Table } from 'primeng/table';
import { PermissionCheckService } from 'src/app/service/permission.check.service';

@Component({
    selector: 'app-client-list',
    templateUrl: './client-list.component.html',
    providers: [ClientService, PermissionCheckService]
})

export class ClientListComponent implements OnInit {
    client: ClientModel | null = null
    clientList: ClientModel[] = [];
    selectedClient: ClientModel[] = [];
    cols: any[] = [];
    deleteClientDialog: boolean = false;
    module = 'Client';
    constructor(private _clientService: ClientService,
        private _notificationService: NotificationService,
        public _permissionCheck: PermissionCheckService,
        private _router: Router) {
        if (!this.permission('R')) {
            this.goBack();
        }
    }

    ngOnInit() {
        this.cols = [
            { field: 'full_name', header: 'full_name' },
            { field: 'address', header: 'address' },
            { field: 'region', header: 'region' },
            { field: 'teams_link', header: 'teams_link' },
        ];
        this.getAll();
    }
    goBack() {
        this._router.navigate([`client/client-dashboard`]);
    }
    permission(crud: string) {
        return this._permissionCheck.check(this.module, crud);
    }
    getAll() {
        // this._clientService.getAll().subscribe((res: APIResponse<Array<ClientModel>>) => {
        //     this.clientList = res.results
        // })
        this._clientService.getAll().subscribe((res: any) => {
            this.clientList = res.results.data
        });
    }

    gotoClientDashboard(id: number) {
        this._router.navigate([`client/client-dashboard/${id}`]);
    }

    onGlobalFilter(table: Table, event: Event) {
        table.filterGlobal((event.target as HTMLInputElement).value, 'contains');
    }

    addEditClient(id: string) {
        this._router.navigate([`/client/index/${id}`]);
    }

    deleteClient(row: ClientModel) {
        this.client = row;
        this.deleteClientDialog = true;
    }

    confirmDelete() {
        this.deleteClientDialog = false;
        this._clientService.delete(this.client?.id as number).subscribe(res => {
            this._notificationService.success('Client Deleted');
            this.getAll();
        });
    }


}
