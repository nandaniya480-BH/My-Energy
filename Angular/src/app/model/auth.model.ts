import { ClientModel } from "./client.model"
import { PermissionModel } from "./permission.model"

export interface SignInModel {
    user_name: string,
    password: string
}

export interface SignUpModel {
    emailAddress: string,
    password: string,
    firstName: string,
    lastName: string,
    contactNo: string
}

export interface SignInResponse {
    access_token: string,
    permission:PermissionModel[],
    client:ClientModel
}


export interface SignUpResponse {
    token: string
}
