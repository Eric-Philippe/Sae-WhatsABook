import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import {
  faClock,
  faHandshakeAngle,
  faSchool,
} from '@fortawesome/free-solid-svg-icons';
import Session from 'src/app/Middlewares/Session';

@Component({
  selector: 'app-support',
  templateUrl: './support.page.html',
  styleUrls: ['./support.page.css'],
})
export class SupportPage implements OnInit {
  isConnected = false;
  success = false;

  faHandshake = faHandshakeAngle;
  faClock = faClock;
  faSchool = faSchool;

  email: string | null = null;

  suggestForm: FormGroup;

  constructor(private fb: FormBuilder) {
    this.suggestForm = this.fb.group({
      title: [''],
      topic: [''],
      email: [''],
      category: ['Demande de support'],
      description: [''],
    });
  }

  async ngOnInit(): Promise<void> {
    const session = await Session.getInstance();
    this.isConnected = session.isOpen;
    if (this.isConnected) this.email = session.user.email;
  }

  async onSubmit(): Promise<void> {
    this.success = true;
    this.suggestForm.reset();
  }
}
