import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { APIResponse } from 'src/app/model/api-response.model';
import { ClientModel } from 'src/app/model/client.model';
import { ClientConsumptionPlanService } from 'src/app/service/client-consumption-plan.service';
import { ClientService } from 'src/app/service/client.service';
import { NotificationService } from 'src/app/service/notification.service';
import { PermissionCheckService } from 'src/app/service/permission.check.service';

@Component({
  selector: 'app-client-dashboard',
  templateUrl: './client-dashboard.component.html',
  providers: [ClientService, ClientConsumptionPlanService,PermissionCheckService]
})
export class ClientDashboardComponent implements OnInit {
  client: ClientModel = {
    id: 0,
    full_name: '',
    address: '',
    region: '',
    teams_link: ''
  };

  consuptionData: any;
  avg: any;
  sum: any;
  min: any;
  max: any;
  data: any;
  options: any;
  fromDate: Date | undefined;
  toDate: Date | undefined;

  constructor(private _notificationService: NotificationService,
    private _clientConsPService: ClientConsumptionPlanService,
    public _permissionCheck: PermissionCheckService,
    private _clientService: ClientService,
    private _router: Router,
    private _route: ActivatedRoute,) {
    this._route.params.subscribe(params => {
      this.client.id = params['id'];
      if (!(this.client.id > 0)) {
        const c = JSON.parse(sessionStorage.getItem('client') || '{}');
        var i = c.client_id > 0 ? c.client_id:c.id;
        if (!( i > 0)) {
          this.goBack();
        }
        this.client.id = i;
      }

    });
  }

  ngOnInit() {
    this.sum = 0;
    this.min = 0;
    this.max = 0;
    this.avg = 0;
    this.consuptionData = [];

    this._clientService.get(this.client.id).subscribe((res: APIResponse<ClientModel>) => {
      this.client = res.results;
      this.loadChartData();
    });

  }

  permission(module:string,crud: string) {
    return this._permissionCheck.check(module, crud);
  }

  sortArrayByDate(list: any) {
    return list.sort((a: any, b: any) => {
      let dateA = new Date(a.DateTime);
      let dateB = new Date(b.DateTime);
      return dateA.getTime() - dateB.getTime();
    });
  }

  filterDataByDateRange(fromDate: Date, toDate: Date) {
    return this.consuptionData.filter((item: any) => {
      const date = new Date(item.DateTime);
      const fromDateObj = fromDate;
      const toDateObj = toDate;
      return date >= fromDateObj && date <= toDateObj;
    });
  }


  generateRandomData(numOfEntries: any) {
    const newData = [];

    const clients = ['HONS', 'ABC Corp', 'XYZ Ltd']; // Sample client names
    const plans = ['Friday', 'Monday', 'Wednesday']; // Sample client plans

    for (let i = 0; i < numOfEntries; i++) {
      const randomYear = this.getRandomInt(20, 23).toString().padStart(2, '0'); // Random year between 2022 and 2025
      const randomMonth = this.getRandomInt(1, 12).toString().padStart(2, '0'); // Random month between 01 and 12
      const randomDay = this.getRandomInt(1, 28).toString().padStart(2, '0'); // Random day between 01 and 28 (considering February as 28 days)
      const randomHour = this.getRandomInt(0, 23).toString().padStart(2, '0'); // Random hour between 00 and 23

      const randomClient = clients[this.getRandomInt(0, clients.length - 1)]; // Random client from the clients array
      const randomPlan = plans[this.getRandomInt(0, plans.length - 1)]; // Random plan from the plans array

      const newItem = {
        "Year": randomYear,
        "Month": randomMonth,
        "Day": randomDay,
        "Hour": randomHour,
        "Client": randomClient,
        "Client Plan": randomPlan,
        "Consumtion": (Math.random() * (5 - 1) + 1).toFixed(2), // Random consumption between 1.00 and 4.99
        "Status": "Pending",
        "DateTime": `20${randomYear}-${randomMonth}-${randomDay}T${randomHour}:00:00.000Z`
      };

      newData.push(newItem);
    }

    return newData;
  }
  getRandomInt(min: any, max: any) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
  }

  formatDateToMMYYYY(date:any) {
    const month = (date.getMonth() + 1).toString().padStart(2, '0'); // Adding 1 because getMonth() returns zero-based index
    const year = date.getFullYear().toString();
  
    return `${month}-${year}`;
  }

  loadChartData() {

    this._clientConsPService.getdatabydaterange(this.client.full_name, this.fromDate, this.toDate).subscribe((res: any) => {
      this.consuptionData = res.results.data || [];
      this.consuptionData = this.sortArrayByDate(this.consuptionData);
      for (let x of this.consuptionData) {
        const d = new Date(x.created_at);
        x.YearMonth = this.formatDateToMMYYYY(d);
      }
      let consumptions = this.consuptionData.map((item: any) => parseFloat(item.consumption));
      this.sum = consumptions.reduce((acc: any, val: any) => acc + val, 0).toFixed(2);
      this.avg = (this.sum / consumptions.length).toFixed(2);
      this.min = Math.min(...consumptions).toFixed(2);
      this.max = Math.max(...consumptions).toFixed(2);

      const documentStyle = getComputedStyle(document.documentElement);
      const textColor = documentStyle.getPropertyValue('--text-color');
      const textColorSecondary = documentStyle.getPropertyValue('--text-color-secondary');
      const surfaceBorder = documentStyle.getPropertyValue('--surface-border');

      let uniqueLabels = Array.from(new Set(this.consuptionData.map((obj: any) => obj.YearMonth)));

      let Client = Array.from(new Set(this.consuptionData.map((obj: any) => obj.client_plan.short_name)));

      let datasets = [];
      for (let x of Client || []) {
        let data = this.consuptionData.filter((item: any) => item.client_plan.short_name == x).map((item: any) => item.consumption);
        const randomColor1 = `rgb(${this.getRandomInt(0, 255)}, ${this.getRandomInt(0, 255)}, ${this.getRandomInt(0, 255)})`;
        const randomColor2 = `rgb(${this.getRandomInt(0, 255)}, ${this.getRandomInt(0, 255)}, ${this.getRandomInt(0, 255)})`;
        datasets.push({
          label: x,
          backgroundColor: randomColor1,
          borderColor: randomColor2,
          data: data
        });
      }


      this.data = {
        labels: uniqueLabels,
        datasets: datasets
      };

      this.options = {
        maintainAspectRatio: false,
        aspectRatio: 0.8,
        plugins: {
          legend: {
            labels: {
              color: textColor
            }
          }
        },
        scales: {
          x: {
            ticks: {
              color: textColorSecondary,
              font: {
                weight: 500
              }
            },
            grid: {
              color: surfaceBorder,
              drawBorder: false
            }
          },
          y: {
            ticks: {
              color: textColorSecondary
            },
            grid: {
              color: surfaceBorder,
              drawBorder: false
            }
          }

        }
      };
    });


  }

  goBack() {
    this._router.navigate([`client/list`]);
  }

  gotoClientUserList() {
    this._router.navigate([`client/client-user/${this.client.id}`]);
  }

  gotoClientPlansList() {
    this._router.navigate([`client/client-plan/${this.client.id}`]);
  }

  gotoConsumptionPlanList() {
    this._router.navigate([`client/client-consumption-plan/${this.client.id}`]);
  }
}
