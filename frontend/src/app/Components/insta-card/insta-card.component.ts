import { Component, Input } from '@angular/core';

export interface InstaPost {
  text: string;
  likes: number;
  image: string;
}

@Component({
  selector: 'app-insta-card',
  templateUrl: './insta-card.component.html',
  styleUrls: ['./insta-card.component.css'],
})
export class InstaCardComponent {
  @Input() post: InstaPost = {} as InstaPost;
  postText = '';

  ngOnInit() {
    this.postText = this.highlightHashtags(this.post.text);
  }

  highlightHashtags(text: string): string {
    return text.replace(/#(\w+)/g, '<span class="hashtag">$&</span>');
  }
}
