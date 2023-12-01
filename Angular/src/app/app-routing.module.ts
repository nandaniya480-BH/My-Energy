import { RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';
import { NotfoundComponent } from './pages/notfound/notfound.component';
import { AppLayoutComponent } from './layout/app.layout.component';
import { AuthGuard } from './auth.guard';

@NgModule({
    imports: [
        RouterModule.forRoot([
            {
                path: '', component: AppLayoutComponent,
                children: [
                    { path: '', redirectTo: '/client/client-dashboard', pathMatch: 'full' },
                    {
                        path: 'dashboard',
                        canActivate: [AuthGuard],
                        loadChildren: () => import('./pages/dashboard/dashboard.module').then(m => m.DashboardModule)
                    },
                    // {
                    //     path: 'profile',
                    //     canActivate: [AuthGuard],
                    //     loadChildren: () => import('./pages/profile/profile.module').then(m => m.ProfileModule)
                    // },
                    {
                        path: 'client',
                        canActivate: [AuthGuard],
                        loadChildren: () => import('./pages/client/client.module').then(m => m.ClientdModule)
                    }
                ]
            },
            { path: 'auth', loadChildren: () => import('./pages/auth/auth.module').then(m => m.AuthModule) },
            { path: 'notfound', component: NotfoundComponent },
            { path: '**', redirectTo: '/notfound' },
        ], { scrollPositionRestoration: 'enabled', anchorScrolling: 'enabled', onSameUrlNavigation: 'reload' })
    ],
    exports: [RouterModule]
})
export class AppRoutingModule {
}
