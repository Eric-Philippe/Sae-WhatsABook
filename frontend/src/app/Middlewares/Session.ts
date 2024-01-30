import axios from 'axios';
import API_URL from '../URL';
import { Member } from '../models/Member';

export default class Session {
  private static instance: Session;
  private _token: string;
  private _user: Member;
  private constructor() {
    this._token = '';
    this._user = {} as Member;
  }
  public static getInstance(): Session {
    if (!Session.instance) {
      Session.instance = new Session();
    }
    return Session.instance;
  }
  public get token(): string {
    return this._token;
  }
  public set token(token: string) {
    this._token = token;
  }
  public get user(): any {
    return this._user;
  }
  public set user(user: any) {
    this._user = user;
  }

  public async logout() {
    this.token = '';
    this.user = {} as Member;
  }

  public async login(email: string, password: string) {
    const res = await axios.post(API_URL('/login_check'), {
      email: email,
      password: password,
    });

    if (res.status === 401) {
      throw new Error('Invalid credentials');
    }

    // Save the token
    this.token = res.data.token;
    localStorage.setItem('token', this.token);

    this.fetchUser();
  }

  public async fetchUser() {
    const res = await axios.get(API_URL('/user/me'), {
      headers: { Authorization: `Bearer ${this.token}` },
    });

    const user = res.data;
    this.user.id = user.id;
    this.user.email = user.email;
    this.user.firstname = user.firstname;
    this.user.lastname = user.lastname;
    this.user.role = this.getRoleEnum(user.roles);
  }

  public getRoleEnum(role: string) {
    switch (role) {
      case 'ROLE_ADMIN':
        return 'ROLE_ADMIN';
      default:
        return null;
    }
  }
}
