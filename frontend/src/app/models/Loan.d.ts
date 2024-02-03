import { Book } from './Book';

export type Loan = {
  id: string;
  loanDate: string;
  returnDate: Date | null;
  book: Book;
};
