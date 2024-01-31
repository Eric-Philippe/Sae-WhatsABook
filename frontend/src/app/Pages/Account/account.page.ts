import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';

import axios from 'axios';

import { ProtectedPage } from 'src/app/Middlewares/ProtectedPage';
import API_URL from 'src/app/URL';

import { FullMember, PhpDate } from 'src/app/models/FullMember';
import { Member } from 'src/app/models/Member';

import {
  faCalendar,
  faEnvelope,
  faFingerprint,
  faHouse,
  faKey,
  faLocationDot,
  faLock,
  faPhone,
  faUser,
} from '@fortawesome/free-solid-svg-icons';

@Component({
  selector: 'app-account',
  templateUrl: './account.page.html',
  styleUrls: ['./account.page.css'],
})
export class AccountPage extends ProtectedPage {
  memberInfo: FullMember = {} as FullMember;
  changeInfoForm: FormGroup;
  changePasswordFrom: FormGroup;

  changeInfoSuccess: boolean | null = null;
  changePwdSuccess: boolean | null = null;
  errorMsg: string = '';

  faFingerprintIcon = faFingerprint;
  faUserIcon = faUser;
  faMailIcon = faEnvelope;
  faPhoneIcon = faPhone;
  faAdressIcon = faLocationDot;
  faCalendarIcon = faCalendar;
  faHouseIcon = faHouse;
  fakeKeyIcon = faKey;
  faLockIcon = faLock;

  constructor(private router: Router, private fb: FormBuilder) {
    super(router);
    this.changeInfoForm = this.fb.group({
      firstname: ['', [Validators.required]],
      lastname: ['', [Validators.required]],
      email: ['', [Validators.required, Validators.email]],
      phone: ['', [Validators.required]],
      address: ['', [Validators.required]],
    });

    this.changePasswordFrom = this.fb.group({
      password: ['', [Validators.required]],
      newPassword: ['', [Validators.required]],
      newPasswordConfirm: ['', [Validators.required]],
    });
  }

  override async ngOnInit(): Promise<void> {
    await super.ngOnInit();

    const res = await axios.get(API_URL('/user/me/full'), {
      headers: { Authorization: `Bearer ${this.session?.token}` },
    });
    this.memberInfo = res.data;
    console.log(this.memberInfo);

    this.changeInfoForm.patchValue({
      firstname: this.memberInfo.firstname,
      lastname: this.memberInfo.lastname,
      email: this.memberInfo.email,
      phone: this.memberInfo.phone,
      address: this.memberInfo.address,
    });
  }

  getPseudo(): string {
    if (!this.memberInfo.firstname || !this.memberInfo.lastname) return '';
    return (
      this.memberInfo.firstname.toLocaleLowerCase() +
      '.' +
      this.memberInfo.lastname.toLocaleLowerCase()
    );
  }

  getBirthDate(phpDate: PhpDate): string {
    if (!phpDate) return '';
    const date = new Date(phpDate.date);
    let month = date.getMonth() + 1;
    let monthStr = month < 10 ? '0' + month : month;
    return `${date.getDate()}/${monthStr}/${date.getFullYear()}`;
  }

  getPushPreference(): number {
    if (!this.memberInfo.firstname) return 0;
    // Take the position of the first letter of the firstname in the alphabet
    let letterPos =
      this.memberInfo.firstname.toLocaleLowerCase().charCodeAt(0) - 97;
    // Make it modulo 3
    return (letterPos + 1) % 3;
  }

  async onSubmitChangeToInformations(): Promise<void> {
    try {
      const res = await axios.put(
        API_URL('/user/me/update'),
        {
          firstname: this.changeInfoForm.value.firstname,
          lastname: this.changeInfoForm.value.lastname,
          email: this.changeInfoForm.value.email,
          phone: this.changeInfoForm.value.phone,
          address: this.changeInfoForm.value.address,
        },
        {
          headers: { Authorization: `Bearer ${this.session?.token}` },
        }
      );

      if (res.status === 200) {
        this.memberInfo.firstname = this.changeInfoForm.value.firstname;
        this.memberInfo.lastname = this.changeInfoForm.value.lastname;
        this.memberInfo.email = this.changeInfoForm.value.email;
        this.memberInfo.phone = this.changeInfoForm.value.phone;
        this.memberInfo.address = this.changeInfoForm.value.address;

        const member: Member = {
          id: this.memberInfo.id,
          email: this.memberInfo.email,
          firstname: this.memberInfo.firstname,
          lastname: this.memberInfo.lastname,
          role: this.session?.user.role,
        };

        this.session?.updateUser(member);
        this.changeInfoSuccess = true;
      } else {
        this.changeInfoSuccess = false;
        this.errorMsg =
          "Une erreur s'est produite. Merci de vérifier vos données";
      }
    } catch (err) {
      this.changeInfoSuccess = false;
      this.errorMsg =
        "Une erreur s'est produite. Merci de vérifier vos données";
    }
  }

  async onSubmitNewPassword(): Promise<void> {
    console.log(this.changePasswordFrom.value);

    if (
      this.changePasswordFrom.value.newPassword !==
      this.changePasswordFrom.value.newPasswordConfirm
    ) {
      this.errorMsg = 'Les mots de passe ne correspondent pas';
      this.changePwdSuccess = false;
      return;
    }

    const res = await axios.put(
      API_URL('/user/password/update'),
      {
        password: this.changePasswordFrom.value.password,
        newPassword: this.changePasswordFrom.value.newPassword,
      },
      {
        headers: { Authorization: `Bearer ${this.session?.token}` },
      }
    );

    if (res.status === 200) {
      this.changePasswordFrom.reset();
      this.changePwdSuccess = true;
    }
  }
}
