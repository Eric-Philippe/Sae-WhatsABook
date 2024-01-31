import { PhpDate } from './FullMember';

export type Author = {
  id: string;
  lastname: string;
  firstname: string;
  birthdate: PhpDate;
  deathdate: PhpDate | null;
  nationality: string;
  photoLink: string;
  description: string;
};
