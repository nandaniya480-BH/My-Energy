import { Component, ElementRef, ViewChild } from '@angular/core';
import { MenuItem } from 'primeng/api';
import { LayoutService } from "./service/app.layout.service";
import { Router } from '@angular/router';
import { AuthService } from '../service/auth.service';
import { NotificationService } from '../service/notification.service';

@Component({
    selector: 'app-topbar',
    templateUrl: './app.topbar.component.html',
    providers: [AuthService]
})
export class AppTopBarComponent {

    items: MenuItem[] | undefined;

    @ViewChild('menubutton') menuButton!: ElementRef;

    @ViewChild('topbarmenubutton') topbarMenuButton!: ElementRef;

    @ViewChild('topbarmenu') menu!: ElementRef;

    constructor(public layoutService: LayoutService,
        private _router: Router,
        private _authService: AuthService,
        private _notificationService: NotificationService) { }

    ngOnInit() {
        this.items = [
            {
                items: [
                    {
                        label: 'Profile',
                        icon: 'pi pi-user',
                        routerLink: '/profile'
                    },
                    {
                        label: 'Sign out',
                        icon: 'pi pi-sign-out',
                        command: () => {
                            this._authService.signout().subscribe((res) => {
                                if (res.message == 'Logout Successfully') {
                                    sessionStorage.clear();
                                    this._router.navigate(['/auth/sign-in']);
                                }
                                else {
                                    this._notificationService.error('Error in logout');
                                }
                            })

                        }
                    }
                ]
            },
        ];
    }
}
