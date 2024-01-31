import { Author } from './Author';
import { Category } from './Category';
import { PhpDate } from './FullMember';
import { Loan } from './Loan';
import { Reservation } from './Reservation';

export type Book = {
  id: string;
  title: string;
  summary: string;
  releaseDate: PhpDate;
  language: string;
  coverLink: string;
  authors: Author[];
  categories: Category[];
  reservations: Reservation[];
  loans: Loan[];
};
