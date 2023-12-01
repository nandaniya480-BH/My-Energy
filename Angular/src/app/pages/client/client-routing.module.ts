import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { ClientListComponent } from './client-list/client-list.component';
import { ClientComponent } from './client/client.component';
import { ClientUserComponent } from './client-user/client-user.component';
import { ClientDashboardComponent } from './client-dashboard/client-dashboard.component';
import { ClientPlansComponent } from './client-plans/client-plans.component';
import { ClientConsumptionPlanComponent } from './client-consumption-plan/client-consumption-plan.component';

@NgModule({
    imports: [RouterModule.forChild([
        { path: 'index/:id', component: ClientComponent },
        { path: 'list', component: ClientListComponent },
        { path: 'client-user/:id', component: ClientUserComponent },
        { path: 'client-dashboard/:id', component: ClientDashboardComponent },
        { path: 'client-dashboard', component: ClientDashboardComponent },
        { path: 'client-plan/:id', component: ClientPlansComponent },
        { path: 'client-consumption-plan/:id', component: ClientConsumptionPlanComponent },
    ])],
    exports: [RouterModule]
})
export class ClientRoutingModule { }
