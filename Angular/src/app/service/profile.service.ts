import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { ProfileModel } from '../model/profile.model';
import { Observable } from 'rxjs';

@Injectable()
export class ProfileService {

    baseUrl: string = environment.apiUrl
    constructor(private http: HttpClient) { }

    get(): Observable<ProfileModel> {
        return this.http.get<ProfileModel>(`${this.baseUrl}profile`);
    }

    update(profileModel: ProfileModel): Observable<ProfileModel> {
        return this.http.post<ProfileModel>(`${this.baseUrl}profile`, profileModel);
    }
}
