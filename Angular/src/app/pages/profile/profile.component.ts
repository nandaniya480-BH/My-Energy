import { Component } from '@angular/core';
import { ProfileModel } from 'src/app/model/profile.model';
import { ProfileService } from 'src/app/service/profile.service';

@Component({
    selector: 'app-profile',
    templateUrl: './profile.component.html',
    providers: [ProfileService]
})

export class ProfileComponent {
    profile: ProfileModel | null = null

    constructor(private _profileService: ProfileService) { }
    ngOnInit(): void { }

    getProfile() {
        this._profileService.get().subscribe(res => this.profile = res);
    }

    updateProfile() {
        if (this.profile)
            this._profileService.update(this.profile).subscribe(res => this.profile = res);
    }
}
