import axios from 'axios';
import API_URL from '../URL';
import { Member } from '../models/Member';

export default class Session {
  private static instance: Session;
  private _token: string;
  private _user: Member;
  private _isOpen: boolean;
  private _fetchUserPromise: Promise<boolean> | null;

  private constructor() {
    this._token = '';
    this._user = {} as Member;
    this._isOpen = false;
    this._fetchUserPromise = null;
  }

  public static async getInstance(): Promise<Session> {
    return new Promise(async (resolve, reject) => {
      if (!Session.instance) {
        Session.instance = new Session();
        await Session.instance.createInstance();
      }

      if (Session.instance._fetchUserPromise) {
        await Session.instance._fetchUserPromise; // Attendez la promesse fetchUser si elle existe
      }

      resolve(Session.instance);
    });
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
    if (this.user.id == undefined) this._isOpen = false;
    return this._isOpen;
  }

  private async createInstance(): Promise<void> {
    return new Promise(async (resolve, reject) => {
      if (this.user.id != null) this._isOpen = false;

      let token = localStorage.getItem('token');

      if (token != null) {
        this.token = localStorage.getItem('token') as string;
        try {
          this._fetchUserPromise = this.fetchUser(); // Stockez la promesse fetchUser
          await this._fetchUserPromise; // Attendez la promesse fetchUser
          this._isOpen = true;
          resolve();
        } catch (err) {
          this._isOpen = false;
          reject(err);
        } finally {
          this._fetchUserPromise = null; // Réinitialisez la promesse après son exécution
        }
      }
    });
  }

  public async logout() {
    this.token = '';
    this.user = {} as Member;
    this._isOpen = false;
    localStorage.clear();
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

  public async fetchUser(): Promise<boolean> {
    return new Promise(async (resolve, reject) => {
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
        resolve(true);
      } catch (err) {
        reject(false);
      }
    });
  }

  public updateUser(user: Member) {
    this.user = user;
  }

  public getRoleEnum(role: string) {
    switch (role) {
      case 'ROLE_ADMIN':
        return 'ROLE_ADMIN';
      case 'ROLE_STAFF':
        return 'ROLE_STAFF';
      case 'ROLE_MEMBER':
        return 'ROLE_MEMBER';
      default:
        return null;
    }
  }
}
