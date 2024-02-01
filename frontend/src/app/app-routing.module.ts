import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomePage } from './Pages/Home/home.page';
import { LoginPage } from './Pages/Login/login.page';
import { AccountPage } from './Pages/Account/account.page';
import { SupportPage } from './Pages/Support/support.page';
import { NotFoundPage } from './Pages/404/notfound.page';
import { ConsultPage } from './Pages/Consult/consult.page';
import { SuggestPage } from './Pages/Suggest/suggest.page';
import { ConsultBookPage } from './Pages/ConsultBook/consultbook.page';

const routes: Routes = [
  { path: '', component: HomePage },
  { path: 'login', component: LoginPage },
  { path: 'account', component: AccountPage },
  { path: 'support', component: SupportPage },
  { path: 'consult', component: ConsultPage },
  { path: 'suggest', component: SuggestPage },
  { path: 'consult/:id', component: ConsultBookPage },
  { path: '**', component: NotFoundPage },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule {}
