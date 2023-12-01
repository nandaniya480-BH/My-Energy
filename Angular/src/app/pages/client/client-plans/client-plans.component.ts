import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { NotificationService } from 'src/app/service/notification.service';
import { ClientService } from 'src/app/service/client.service';
import { Table } from 'primeng/table';
import { ClientPlanModel } from 'src/app/model/client-plan.model';
import { ClientPlanService } from 'src/app/service/clientplan.service';
import { ClientModel } from 'src/app/model/client.model';
import { APIResponse } from 'src/app/model/api-response.model';

@Component({
  selector: 'app-client-plans',
  templateUrl: './client-plans.component.html',
  providers: [ClientService,ClientPlanService]
})

export class ClientPlansComponent implements OnInit {
  clientPlanList: ClientPlanModel[] = [];
  cols: any[] = [];
  client: ClientModel = {
    id: 0,
    full_name: '',
    address: '',
    region: '',
    teams_link: ''
  };
  constructor(private _clientPlanService: ClientPlanService,
    private _clientService: ClientService,
    private _notificationService: NotificationService,
    private _router: Router,
    private _route: ActivatedRoute,) {
      this._route.params.subscribe(params => {
        this.client.id = params['id'];
        if (!(this.client.id > 0)) {
          this.goBack();
        }
        else {
          this._clientService.get(this.client.id).subscribe((res: APIResponse<ClientModel>) => {
            this.client = res.results;
          });
        }
      });
     }

  ngOnInit() {
    this.cols = [
      { field: 'short_name', header: 'short_name' },
      { field: 'description', header: 'description' },
      { field: 'status', header: 'status' },
      { field: 'purchase_source', header: 'purchase_source' },
      { field: 'source_capacity', header: 'source_capacity' },
    ];
    this.getAll();
  }


  getAll() {
    this._clientPlanService.getAll(this.client.id).subscribe((res: any) => {
      this.clientPlanList = res.results;
    });
  }

  goBack() {
    this._router.navigate([`client/client-dashboard/${this.client.id}`]);
  }

  onGlobalFilter(table: Table, event: Event) {
    table.filterGlobal((event.target as HTMLInputElement).value, 'contains');
  }
}
