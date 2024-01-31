export type Suggestion = {
  title: string;
  author: string;
  editor: string;
  releaseDate: string;
  description: string;
  details: string;
};

export type FullSuggestion = Suggestion & {
  id_member: string;
  email_member: string;
};
