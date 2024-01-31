import { Member } from './Member';

export type PhpDate = {
  date: string;
  timezone_type: number;
  timezone: string;
};

export type FullMember = Member & {
  creationDate: PhpDate;
  birthDate: PhpDate;
  address: string;
  phone: string;
  photoLink: string;
};
