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
import { SupportPage } from './Pages/Support/support.page';
import { NotFoundPage } from './Pages/404/notfound.page';
import { ConsultPage } from './Pages/Consult/consult.page';
import { BookCardComponent } from './Components/book-card/book-card.component';
import { SuggestPage } from './Pages/Suggest/suggest.page';
import { ConsultBookPage } from './Pages/ConsultBook/consultbook.page';
import { ReviewCardComponent } from './Components/review-card/review-card.component';
import { FooterComponent } from './Components/footer/footer.component';
import { InstaCardComponent } from './Components/insta-card/insta-card.component';
import { SelectionPage } from './Pages/Selection/selection.page';
import { AdherentPage } from './Pages/Adherent/adherent.page';
import { ReservationCardComponent } from './Components/reservation-card/reservation-card.component';
import { LoanCardComponent } from './Components/loan-card/loan-card.component';

@NgModule({
  declarations: [
    AppComponent,
    HeaderComponent,
    HomePage,
    LoginPage,
    AppComponent,
    AccountPage,
    SupportPage,
    NotFoundPage,
    ConsultPage,
    BookCardComponent,
    SuggestPage,
    ConsultBookPage,
    ReviewCardComponent,
    FooterComponent,
    InstaCardComponent,
    SelectionPage,
    AdherentPage,
    ReservationCardComponent,
    LoanCardComponent,
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
