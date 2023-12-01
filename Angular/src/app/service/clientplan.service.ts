import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { Observable } from 'rxjs';
import { APIResponse } from '../model/api-response.model';
import { ClientPlanModel } from '../model/client-plan.model';

@Injectable()
export class ClientPlanService {

    baseUrl: string = environment.apiUrl;
    constructor(private http: HttpClient) { }

    getAll(client_id:number): Observable<APIResponse<Array<ClientPlanModel>>> {
        return this.http.get<APIResponse<Array<ClientPlanModel>>>(`${this.baseUrl}/client-plans/${client_id}`);
    }
}
