import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { environment } from '../../environments/environment';
import { ClientConsumptionPlanModel } from '../model/client-consumption-plan.model';
import { Observable } from 'rxjs';
import { APIResponse } from '../model/api-response.model';

@Injectable()
export class ClientConsumptionPlanService {

    baseUrl: string = environment.apiUrl
    constructor(private http: HttpClient) { }

    getAll(client_full_name: string): Observable<APIResponse<Array<ClientConsumptionPlanModel>>> {
        return this.http.get<APIResponse<Array<ClientConsumptionPlanModel>>>(`${this.baseUrl}/consumption-plan-index/${client_full_name}`);
    }
    get(id: number): Observable<APIResponse<ClientConsumptionPlanModel>> {
        return this.http.get<APIResponse<ClientConsumptionPlanModel>>(`${this.baseUrl}/consumption-plan/${id}`);
    }
    getdatabydaterange(client_full_name: string,fromDate?:Date, toDate?:Date): Observable<APIResponse<Array<ClientConsumptionPlanModel>>> {
        const obj = {
            from_date:fromDate,
            to_date:toDate
        }
        return this.http.post<APIResponse<Array<ClientConsumptionPlanModel>>>(`${this.baseUrl}/consumption-plan-data/${client_full_name}`,obj);
    }
    save(clientConsPModel: ClientConsumptionPlanModel): Observable<ClientConsumptionPlanModel> {
        return this.http.post<ClientConsumptionPlanModel>(`${this.baseUrl}/consumption-plan`, clientConsPModel);
    }
    update(clientConsPModel: ClientConsumptionPlanModel): Observable<ClientConsumptionPlanModel> {
        return this.http.put<ClientConsumptionPlanModel>(`${this.baseUrl}/consumption-plan/${clientConsPModel.id}`, clientConsPModel);
    }
    delete(id: number): Observable<number> {
        return this.http.delete<number>(`${this.baseUrl}/consumption-plan/${id}`);
    }
}
