import { Component } from '@angular/core';
import { AbstractControl, FormBuilder, FormGroup, Validators, } from '@angular/forms';
import { Router } from '@angular/router';
import { APIResponse } from 'src/app/model/api-response.model';
import { SignUpResponse } from 'src/app/model/auth.model';
import { AuthService } from 'src/app/service/auth.service';

@Component({
    selector: 'app-sign-up',
    templateUrl: './sign-up.component.html',
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

export class SignUpComponent {

    form: FormGroup;
    submitted = false;

    constructor(private formBuilder: FormBuilder, private _authService: AuthService, private _router: Router) {
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

    get f(): { [key: string]: AbstractControl } { return this.form.controls; }

    onSubmit(): void {
        this.submitted = true;
        if (this.form.invalid) return;
        this._authService.signUp(this.form.value).subscribe((res:APIResponse<SignUpResponse>) => {
            sessionStorage.setItem('token', res?.results?.token)
            this._router.navigate(['/']);
        })
    }
}
