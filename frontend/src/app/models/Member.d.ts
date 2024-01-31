import { Roles } from './Roles';

export type Member = {
  id: string;
  email: string;
  firstname: string;
  lastname: string;
  role: Roles | null;
};
