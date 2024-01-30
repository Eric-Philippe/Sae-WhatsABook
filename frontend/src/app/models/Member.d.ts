import { Roles } from './Roles';

export interface Member {
  id: string;
  email: string;
  firstname: string;
  lastname: string;
  role: Roles | null;
}
