import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ClientRoutingModule } from './client-routing.module';
import { ClientComponent } from './client/client.component';




import { FormsModule } from '@angular/forms';
import { TableModule } from 'primeng/table';
import { CalendarModule } from 'primeng/calendar';
import { FileUploadModule } from 'primeng/fileupload';
import { ButtonModule } from 'primeng/button';
import { RippleModule } from 'primeng/ripple';
import { ToastModule } from 'primeng/toast';
import { ToolbarModule } from 'primeng/toolbar';
import { RatingModule } from 'primeng/rating';
import { InputTextModule } from 'primeng/inputtext';
import { InputTextareaModule } from 'primeng/inputtextarea';
import { DropdownModule } from 'primeng/dropdown';
import { RadioButtonModule } from 'primeng/radiobutton';
import { InputNumberModule } from 'primeng/inputnumber';
import { DialogModule } from 'primeng/dialog';
import { ChartModule } from 'primeng/chart';


import { ClientListComponent } from './client-list/client-list.component';
import { ClientUserComponent } from './client-user/client-user.component';
import { ClientDashboardComponent } from './client-dashboard/client-dashboard.component';
import { ClientPlansComponent } from './client-plans/client-plans.component';
import { ClientConsumptionPlanComponent } from './client-consumption-plan/client-consumption-plan.component';
@NgModule({
    imports: [
        CommonModule,
        ClientRoutingModule,
        CommonModule,
        TableModule,
        FileUploadModule,
        FormsModule,
        ButtonModule,
        RippleModule,
        ToastModule,
        ToolbarModule,
        RatingModule,
        InputTextModule,
        InputTextareaModule,
        DropdownModule,
        RadioButtonModule,
        InputNumberModule,
        DialogModule,
        CalendarModule,
        ChartModule
    ],
    declarations: [ClientComponent,ClientListComponent, ClientUserComponent, ClientDashboardComponent, ClientPlansComponent, ClientConsumptionPlanComponent]
})
export class ClientdModule { }
