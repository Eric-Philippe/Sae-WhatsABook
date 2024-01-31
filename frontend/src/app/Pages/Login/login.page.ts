import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import Session from 'src/app/Middlewares/Session';

@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.css'],
})
export class LoginPage implements OnInit {
  loginForm: FormGroup;
  wrongCredentials = false;
  session: Session | null = null;

  async ngOnInit(): Promise<void> {
    // Session.getInstance().isOpen ? this.router.navigate(['/']) : null;
    this.session = await Session.getInstance();
    this.session.isOpen ? this.router.navigate(['/']) : null;
  }

  constructor(private router: Router, private fb: FormBuilder) {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.required],
    });
  }

  async onSubmit() {
    const email = this.loginForm.get('email')?.value;
    const password = this.loginForm.get('password')?.value;

    if (!email || !password) {
      return this.loginForm.markAllAsTouched();
    }

    try {
      await this.session?.login(email, password);
      this.loginForm.reset();
      this.router.navigate(['/']);
    } catch (err) {
      this.wrongCredentials = true;
      return;
    }
  }
}
