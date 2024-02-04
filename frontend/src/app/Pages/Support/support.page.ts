import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import {
  faClock,
  faHandshakeAngle,
  faSchool,
} from '@fortawesome/free-solid-svg-icons';
import emailjs, { EmailJSResponseStatus } from '@emailjs/browser';
import Session from 'src/app/Middlewares/Session';

const PUBLIC_KEY = 'eoPJ5KwYUNr5YwcQL';
const SERVICE_ID = 'service_gmqxltg';
const TEMPLATE_ID = 'template_557kmya';

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
    const session = await Session.getInstance();
    try {
      emailjs.send(
        SERVICE_ID,
        TEMPLATE_ID,
        {
          name: session.user?.firstname + ' ' + session.user?.lastname,
          email: session.user?.email,
          message: this.suggestForm.value.description,
        },
        {
          publicKey: PUBLIC_KEY,
        }
      );
      this.success = true;
      this.suggestForm.reset();
    } catch (err) {}
  }
}
