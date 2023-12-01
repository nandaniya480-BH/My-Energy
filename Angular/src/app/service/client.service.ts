import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { ClientModel } from '../model/client.model';
import { Observable } from 'rxjs';
import { APIResponse } from '../model/api-response.model';

@Injectable()
export class ClientService {

    baseUrl: string = environment.apiUrl
    constructor(private http: HttpClient) { }

    getAll(): Observable<APIResponse<Array<ClientModel>>> {
        return this.http.get<APIResponse<Array<ClientModel>>>(`${this.baseUrl}/client`);
    }
    get(id: number): Observable<APIResponse<ClientModel>> {
        return this.http.get<APIResponse<ClientModel>>(`${this.baseUrl}/client/${id}`);
    }
    save(clientModel: ClientModel): Observable<ClientModel> {
        return this.http.post<ClientModel>(`${this.baseUrl}/client`, clientModel);
    }
    update(clientModel: ClientModel): Observable<ClientModel> {
        return this.http.put<ClientModel>(`${this.baseUrl}/client/${clientModel.id}`, clientModel);
    }
    delete(id: number): Observable<number> {
        return this.http.delete<number>(`${this.baseUrl}/client/${id}`);
    }
}
