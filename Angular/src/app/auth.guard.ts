import { Injectable } from '@angular/core';
import { Router } from '@angular/router';

@Injectable({
    providedIn: 'root'
})
export class AuthGuard {

    constructor(private _router: Router) { }
    canActivate(): boolean {
        const token = sessionStorage.getItem('token');
        if (token) return true;
        else {
            this._router.navigate(['/auth/sign-in']);
            return false
        }
    }
}