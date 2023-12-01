import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { SignInModel, SignInResponse, SignUpModel, SignUpResponse } from '../model/auth.model';
import { Observable } from 'rxjs';
import { APIResponse } from '../model/api-response.model';

@Injectable()
export class AuthService {

    baseUrl: string = environment.apiUrl
    constructor(private http: HttpClient) { }

    signIn(signInModel: SignInModel): Observable<APIResponse<SignInResponse>> {
        return this.http.post<APIResponse<SignInResponse>>(`${this.baseUrl}/login`, signInModel);
    }

    signUp(signUpModel: SignUpModel): Observable<APIResponse<SignUpResponse>> {
        return this.http.post<APIResponse<SignUpResponse>>(`${this.baseUrl}auth/Register`, signUpModel);
    }

    signout() {
        return this.http.post<APIResponse<SignUpResponse>>(`${this.baseUrl}/logout`, {});
    }
}
