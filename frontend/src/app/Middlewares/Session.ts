export default class Session {
  private static instance: Session;
  private _token: string;
  private _user: any;
  private constructor() {
    this._token = '';
    this._user = {};
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
}
