export interface APIResponse<T> {
    status: number;
    message: string;
    results: T;
    data: T;
    error: string;
}