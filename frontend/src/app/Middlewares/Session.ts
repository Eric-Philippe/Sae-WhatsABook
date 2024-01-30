import axios from 'axios';
import API_URL from '../URL';
import { Member } from '../models/Member';

export default class Session {
  private static instance: Session;
  private _token: string;
  private _user: Member;
  private _isOpen: boolean;

  private constructor() {
    this._token = '';
    this._user = {} as Member;
    this._isOpen = false;
    this.createInstance();
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
  public get isOpen(): boolean {
    return this._isOpen;
  }

  private async createInstance() {
    if (this.user) this._isOpen = true;
    else if (!localStorage.getItem('token')) this._isOpen = false;
    else {
      this.token = localStorage.getItem('token') as string;
      try {
        await this.fetchUser();
        this._isOpen = true;
      } catch (err) {
        this._isOpen = false;
      }
    }
  }

  public async logout() {
    this.token = '';
    this.user = {} as Member;
    this._isOpen = false;
    localStorage.removeItem('token');
  }

  public async login(email: string, password: string): Promise<boolean> {
    return new Promise(async (resolve, reject) => {
      try {
        const res = await axios.post(API_URL('/login_check'), {
          email: email,
          password: password,
        });

        if (res.status != 200) {
          throw new Error('Invalid credentials');
        }

        // Save the token
        this.token = res.data.token;
        localStorage.setItem('token', this.token);

        await this.fetchUser();
        resolve(true);
      } catch (err) {
        throw err;
      }
    });
  }

  public async fetchUser() {
    try {
      const res = await axios.get(API_URL('/user/me'), {
        headers: { Authorization: `Bearer ${this.token}` },
      });

      const user = res.data;
      this.user.id = user.id;
      this.user.email = user.email;
      this.user.firstname = user.firstname;
      this.user.lastname = user.lastname;
      this.user.role = this.getRoleEnum(user.roles);

      this._isOpen = true;
    } catch (err) {
      throw err;
    }
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
