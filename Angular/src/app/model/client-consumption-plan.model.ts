export interface ClientConsumptionPlanModel {
    id: number;
    client: string;
    client_user: string;
    client_plan: string;
    consumption: number;
    status: string;
    created_at?:Date;
}