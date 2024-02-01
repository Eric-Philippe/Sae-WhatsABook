import { Component, Input } from '@angular/core';
import { Review } from 'src/app/utils/ReviewGenerator';

@Component({
  selector: 'app-review-card',
  templateUrl: './review-card.component.html',
})
export class ReviewCardComponent {
  @Input() review: Review = {} as Review;
}
