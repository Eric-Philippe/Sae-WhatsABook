import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import axios from 'axios';
import emailjs, { EmailJSResponseStatus } from '@emailjs/browser';

const PUBLIC_KEY = 'eoPJ5KwYUNr5YwcQL';
const SERVICE_ID = 'service_gmqxltg';
const TEMPLATE_ID = 'template_clt36qe';

import Session from 'src/app/Middlewares/Session';
import API_URL from 'src/app/URL';
import { Book } from 'src/app/models/Book';
import ReviewGenerator from 'src/app/utils/ReviewGenerator';

type Author = {
  id: string;
  lastname: string;
  firstname: string;
  birthDate: Date;
  deathDate: Date | null;
  nationality: string;
  photoLink: string;
  description: string;
  books: Book[];
};

@Component({
  selector: 'app-consultauthor',
  templateUrl: './consultauthor.page.html',
  styleUrls: ['./consultauthor.page.css'],
})
export class ConsultAuthorPage implements OnInit {
  session!: Session;
  isConnected = false;
  author = {} as Author;

  constructor(private route: ActivatedRoute) {}

  async ngOnInit(): Promise<void> {
    this.session = await Session.getInstance();
    this.isConnected = this.session.isOpen;
    const id = this.route.snapshot.paramMap.get('id');
    const res = await axios.get(API_URL('/author/' + id));
    this.author = res.data;
  }

  getBirthDateStr(): string {
    const date = new Date(this.author.birthDate);
    return date.toLocaleDateString();
  }

  getDeathDateStr(): string {
    if (this.author.deathDate) {
      const date = new Date(this.author.deathDate);
      return date.toLocaleDateString();
    }

    return '';
  }

  getAuthorsBooksCategories(): string {
    const categories = new Set<string>();
    this.author.books.forEach((book) => {
      book.categories.forEach((cat) => {
        categories.add(cat.name);
      });
    });
    return Array.from(categories).join(', ');
  }
}
