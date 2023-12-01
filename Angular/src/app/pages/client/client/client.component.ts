import { Component } from '@angular/core';
import { ClientModel } from 'src/app/model/client.model';
import { NotificationService } from 'src/app/service/notification.service';
import { ClientService } from 'src/app/service/client.service';
import { ActivatedRoute, Router } from '@angular/router';
import { APIResponse } from 'src/app/model/api-response.model';
import { AbstractControl, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { PermissionCheckService } from 'src/app/service/permission.check.service';

@Component({
    selector: 'app-client',
    templateUrl: './client.component.html',
    providers: [ClientService, PermissionCheckService]
})

export class ClientComponent {
    form: FormGroup;
    submitted = false;
    module = 'Client';
    constructor(private formBuilder: FormBuilder,
        private _clientService: ClientService,
        private _notificationService: NotificationService,
        private _route: ActivatedRoute,
        public _permissionCheck: PermissionCheckService,
        private _router: Router) {
        this._route.params.subscribe(params => {
            if (!this.permission('R')) {
                this._router.navigate([`client/client-dashboard`]);
            }
            this.client.id = params['id'];
            if (this.client.id > 0) {
                this._clientService.get(this.client.id).subscribe((res: APIResponse<ClientModel>) => {
                    this.client = res.results;
                });
            }
        });

        this.form = this.formBuilder.group(
            {
                id: [null],
                firstName: ['', [Validators.required]],
                lastName: ['', [Validators.required]],
                contactNo: ['', [Validators.required]],
                emailAddress: ['', [Validators.required, Validators.email]],
                password: ['', [Validators.required, Validators.minLength(8)]]
            },
        );
    }

    client: ClientModel = {
        id: 0,
        full_name: '',
        address: '',
        region: '',
        teams_link: ''
    };

    get f(): { [key: string]: AbstractControl } { return this.form.controls; }
    ngOnInit(): void {

        this.submitted = true;
        if (this.form.invalid) return;
    }
    permission(crud: string) {
        return this._permissionCheck.check(this.module, crud);
    }

    goBack() {
        this._router.navigate([`/client/list`]);
    }

    saveClient() {
        this.submitted = true;
        if (this.client.full_name != null && this.client.full_name != '') {
            if (this.client.id > 0) {
                this._clientService.update(this.client).subscribe(res => {
                    this._notificationService.success('Client Updated');
                    this._router.navigate([`/client/list`]);
                });
            } else {
                this._clientService.save(this.client).subscribe(res => {
                    this._notificationService.success('Client Added');
                    this._router.navigate([`/client/list`]);
                });
            }
        }
    }
}
