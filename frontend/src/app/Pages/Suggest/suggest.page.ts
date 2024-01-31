import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import Session from 'src/app/Middlewares/Session';

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
    this.success = true;
    this.suggestForm.reset();
  }
}
