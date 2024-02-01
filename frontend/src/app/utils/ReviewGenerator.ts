const reviews = [
  "Une superbe aventure, pour toute la famille, un vrai plaisir à lire, c'est original et amusant.",
  "Je l'ai lu deux fois avant de le rendre, je conseille. C'est un livre qui rend heureux!",
  "Il faut aimer les piranhas, mais l'histoire est palpitante, on s'attache bien aux deux héros, on rit et on pleure avec eux.",
  "Je n'ai pas pu le finir sans sortir les mouchoirs, c'est trop triste! Mais si bien écrit! Je ne le recommande pas si vous êtes déprimé...",
  "Alors tout allait un peu trop vite pour moi, ma fille m'a dit que je pouvais lire plus lentement mais je ne suis pas sûre d'avoir tout compris à l'intrigue. Mais ça se passe à Brest et j'aime bien Brest.",
  "Attention, pour ceux qui ne sont pas au courant, un manga ça se lit de droite à gauche, on commence par la fin! Ne vous faites pas piéger comme moi!! Surtout que c'est très amusant.",
  "J'ai pas trop aimé mais ma femme oui et elle lit plus que moi donc elle doit avoir raison. J'ai trouvé qu'il y avait trop de phrases trop longues.",
  "Le meilleur livre que j'ai jamais lu! Il y a des dinosaures dedans et moi j'aime les dinosaures",
  "Un style recherché et délicat, des personnages ciselés, torturés par la vie mais tout de même si beaux dans leurs résignations - et elles sont mutliples. L'auteur s'approche du chef-d'oeuvre, on effleure la perfection. Une perle",
  "C'était pas mal. Sent from my iPhone",
  "Que j'envie les lecteurs qui vont pouvoir découvrir ce livre pour la première fois! J'aimerais être à leur place, et parcourir les pages, partir à l'aventure avec les personnages, comme si je venais de l'ouvrir.",
  "Un petit livre fort sympathique, ça se lit vite, c'est bien ficelé, il y a bien le personnage du prof qui est un peu bizarre, mais ça va.",
  "J'ai été obligé de lire ce livre pour l'école, j'ai eu du mal à le finir, c'est trop long et il y a trop de personnages.",
  'Loved it, really, I recommended it to all my friends, I hope they read it, I hope you read it, it improved my mood so much just plunging into that story.',
  "J'ai pris ça un peu par hasard parce que la couverture était jolie, et la bilbiothécaire qui me l'a conseillé aussi, et franchement ça m'a bien plu, je pense en lire d'autres.",
  "Twitter - pardon, X - m'a dit que c'était mauvais mais ils avaient tous tort. Lisez ce livre, vous ne le regretterez pas, et je vais arrêter de prendre des avis sur un réseau qui limite le nombre de mots pour s'exprimer",
];

const titles = [
  'Magnifique',
  "J'ai adoré!",
  'Bonne lecture',
  'Triste mais bien',
  "Beaucoup d'action",
  'Cool !',
  'Ma femme a aimé',
  'T-Rex',
  'Un délice',
  'Bien',
  'Adorable livre',
  'Sympa',
  'Je sais pas trop',
  'Excellent choice',
  'Merci Madeleine!',
  'Lisez-le !',
];

const dummy_names = [
  'Eric P',
  'Marie F',
  'Nath',
  'Cynthia Lefort',
  'Pierre02',
  'Francis Bouchard',
  'J.P',
  'Juliette',
  'LeCritiqueEveillé',
  'Anonyme',
  'Claire',
  'Karamel',
  'Jason-Kévin',
  'Elizabeth',
  'Fabrice Pelleau',
  'Elon',
];

const short_monthes = [
  'Jan',
  'Feb',
  'Mar',
  'Apr',
  'May',
  'Jun',
  'Jul',
  'Aug',
  'Sep',
  'Oct',
  'Nov',
  'Dec',
];

export interface Review {
  author: string;
  title: string;
  text: string;
  date: string;
  numberReviews?: number;
}

export default class ReviewGenerator {
  private static generateRandomDate() {
    const day = Math.floor(Math.random() * 28) + 1;
    const month = short_monthes[Math.floor(Math.random() * 12)];
    const year = Math.floor(Math.random() * 20) + 2000;
    return `${day} ${month}, ${year}`;
  }

  public static generateRandomReview(): Review {
    // Pick a random number between 0 and the length of the reviews array
    let ran_x = Math.floor(Math.random() * reviews.length);
    return {
      author: dummy_names[ran_x],
      title: titles[ran_x],
      text: reviews[ran_x],
      date: this.generateRandomDate(),
      numberReviews: Math.floor(Math.random() * 100),
    };
  }

  public static generateRandomReviews(max: number): Review[] {
    //let nbTotalReviews = Math.floor(Math.random() * max) + 1;
    let nbTotalReviews = max;
    const reviews: Review[] = [];
    while (nbTotalReviews > 0) {
      let newReview = this.generateRandomReview();
      if (!reviews.find((review) => review.text === newReview.text)) {
        reviews.push(newReview);
        nbTotalReviews--;
      }
    }
    return reviews;
  }
}
