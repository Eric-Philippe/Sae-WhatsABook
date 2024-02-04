import { Author } from './Author';
import { Category } from './Category';
import { Loan } from './Loan';
import { Reservation } from './Reservation';

export type Book = {
  id: string;
  title: string;
  summary: string;
  releaseDate: string;
  language: string;
  coverLink: string;
  pageNumber: number;
  authors: Author[];
  categories: Category[];
  reservation: Reservation | null;
  loans: Loan[];
};
