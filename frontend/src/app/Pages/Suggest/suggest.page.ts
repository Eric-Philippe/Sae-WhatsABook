import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import axios from 'axios';
import Session from 'src/app/Middlewares/Session';
import API_URL from 'src/app/URL';

@Component({
  selector: 'app-suggest',
  templateUrl: './suggest.page.html',
  styleUrls: ['./suggest.page.css'],
})
export class SuggestPage implements OnInit {
  isConnected = false;
  success = false;

  suggestForm: FormGroup;

  constructor(private fb: FormBuilder) {
    this.suggestForm = this.fb.group({
      title: [''],
      author: [''],
      editor: [''],
      releaseDate: [''],
      description: [''],
      details: [''],
    });
  }

  async ngOnInit(): Promise<void> {
    const session = await Session.getInstance();
    this.isConnected = session.isOpen;
  }

  async onSubmit(): Promise<void> {
    try {
      const session = await Session.getInstance();
      const res = await axios.post(
        API_URL('/suggestion/create'),
        {
          memberId: session.user.id,
          bookTitle: this.suggestForm.value.title,
          bookAuthors: this.suggestForm.value.author,
          bookEditor: this.suggestForm.value.editor,
          bookReleaseDate: this.suggestForm.value.releaseDate,
          bookDescription: this.suggestForm.value.description,
          details: this.suggestForm.value.details,
        },
        {
          headers: {
            'Content-Type': 'application/json',
            Authorization: `Bearer ${session.token}`,
          },
        }
      );

      if (res.status === 201) {
        this.success = true;
        this.suggestForm.reset();
      }
    } catch (err) {}
  }
}
