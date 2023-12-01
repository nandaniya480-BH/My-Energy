import { Injectable } from '@angular/core';

@Injectable()
export class PermissionCheckService {
    constructor() { }
    check(module: string, permissionCode: string) {
        const pemission = JSON.parse(sessionStorage.getItem('permission') || '[]');
        const mod = pemission.find((x: any) => x.module == module) || {};
        if (mod.access.includes(permissionCode)) {
            return true;
        }
        return false;
    }
}
