import { OnInit } from '@angular/core';
import { Component } from '@angular/core';
import { LayoutService } from './service/app.layout.service';
import { PermissionModel } from '../model/permission.model';
import { PermissionCheckService } from '../service/permission.check.service';

@Component({
    selector: 'app-menu',
    templateUrl: './app.menu.component.html',
    providers: [PermissionCheckService]
})
export class AppMenuComponent implements OnInit {

    model: any[] = [];
    permission: PermissionModel[] = [];

    constructor(public layoutService: LayoutService,
        public _permissionCheck: PermissionCheckService) {

    }

    ngOnInit() {
        const pages = [];
        pages.push({ label: 'Dashboard', icon: 'pi pi-fw pi-home', routerLink: ['/client/client-dashboard'] });
        if (this._permissionCheck.check('Client', 'R')) {
            pages.push({ label: 'Client', icon: 'pi pi-fw pi-id-card', routerLink: ['/client/list'] });
        }
        
        this.model = [
            {
                label: 'Home',
                items: pages
            }
        ];
    }
}
