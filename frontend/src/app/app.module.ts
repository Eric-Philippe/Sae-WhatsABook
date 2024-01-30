import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { FontAwesomeModule } from '@fortawesome/angular-fontawesome';

import { HeaderComponent } from './Components/header/header.component';
import { HomePage } from './Pages/Home/home.page';
import { LoginPage } from './Pages/Login/login.page';
import { AppComponent } from './app.component';
import { ReactiveFormsModule } from '@angular/forms';
import { AccountPage } from './Pages/Account/account.page';

@NgModule({
  declarations: [
    AppComponent,
    HeaderComponent,
    HomePage,
    LoginPage,
    AppComponent,
    AccountPage,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    ReactiveFormsModule,
    FontAwesomeModule,
  ],
  providers: [],
  bootstrap: [AppComponent],
})
export class AppModule {}
