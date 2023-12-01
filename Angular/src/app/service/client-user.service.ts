import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { ClientUserModel } from '../model/client-user.model';
import { Observable } from 'rxjs';
import { APIResponse } from '../model/api-response.model';
import { RoleModel } from '../model/role.model';

@Injectable()
export class ClientUserService {

    baseUrl: string = environment.apiUrl
    constructor(private http: HttpClient) { }

    getAll(client_id: number): Observable<APIResponse<Array<ClientUserModel>>> {
        return this.http.get<APIResponse<Array<ClientUserModel>>>(`${this.baseUrl}/client-user-index/${client_id}`);
    }
    getRole(role_id: number): Observable<APIResponse<Array<RoleModel>>> {
        return this.http.get<APIResponse<Array<RoleModel>>>(`${this.baseUrl}/get-roles/${role_id}`);
    }

    getAllRole(): Observable<APIResponse<Array<RoleModel>>> {
        return this.http.get<APIResponse<Array<RoleModel>>>(`${this.baseUrl}/get-roles`);
    }

    get(id: number): Observable<APIResponse<ClientUserModel>> {
        return this.http.get<APIResponse<ClientUserModel>>(`${this.baseUrl}/client-user/${id}`);
    }
    save(clientUserModel: ClientUserModel): Observable<ClientUserModel> {
        return this.http.post<ClientUserModel>(`${this.baseUrl}/client-user`, clientUserModel);
    }
    update(clientUserModel: ClientUserModel): Observable<ClientUserModel> {
        return this.http.put<ClientUserModel>(`${this.baseUrl}/client-user/${clientUserModel.id}`, clientUserModel);
    }
    delete(id: number): Observable<number> {
        return this.http.delete<number>(`${this.baseUrl}/client-user/${id}`);
    }
}
