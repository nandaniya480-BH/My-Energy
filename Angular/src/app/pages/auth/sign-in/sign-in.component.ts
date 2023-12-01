import { Component } from '@angular/core';
import { AbstractControl, FormBuilder, FormGroup, Validators, } from '@angular/forms';
import { Router } from '@angular/router';
import { APIResponse } from 'src/app/model/api-response.model';
import { SignInModel, SignInResponse } from 'src/app/model/auth.model';
import { AuthService } from 'src/app/service/auth.service';

@Component({
    selector: 'app-sign-in',
    templateUrl: './sign-in.component.html',
    styles: [`
        :host ::ng-deep .pi-eye,
        :host ::ng-deep .pi-eye-slash {
            transform:scale(1.6);
            margin-right: 1rem;
            color: var(--primary-color) !important;
        }
    `],
    providers: [AuthService]
})

export class SignInComponent {

    form: FormGroup;
    submitted = false;

    constructor(private formBuilder: FormBuilder, private _authService: AuthService, private _router: Router) {
        this.form = this.formBuilder.group(
            {
                emailAddress: ['', [Validators.required]],
                password: ['', [Validators.required, Validators.minLength(8)]]
            },
        );
    }

    get f(): { [key: string]: AbstractControl } { return this.form.controls; }

    onSubmit(): void {
        this.submitted = true;
        if (this.form.invalid) return;

        const obj:SignInModel = {
            user_name:this.form.value.emailAddress,
            password:this.form.value.password
        };

        this._authService.signIn(obj).subscribe((res: APIResponse<SignInResponse>) => {
            sessionStorage.setItem('token', res?.results?.access_token);
            sessionStorage.setItem('permission',JSON.stringify(res?.results?.permission));
            sessionStorage.setItem('client',JSON.stringify(res?.results?.client));
            this._router.navigate(['/client/client-dashboard']);
        })
    }
}
