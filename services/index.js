const mysql = require("mysql");
const fs = require("fs");
const nodemailer = require("nodemailer");

// Configurations de la base de données
const dbConfig = {
  host: "localhost",
  user: "root",
  password: "root",
  database: "WhatsABook",
};

// Get the template from the mail.template.html file
const html_template = fs.readFileSync("mail.template.html", "utf8");

class ReservationCleaner {
  constructor() {
    /** @type {mysql.Connection}*/
    this.connection = mysql.createConnection(dbConfig);
    this.transporter = nodemailer.createTransport({
      service: "gmail",
      auth: {
        user: "whatsabook.norepy@gmail.com",
        pass: "mgew htci rnfx jfba",
      },
    });
    /**  @type {Array<{id: string, email: string, title: string}>} */
    this.outdatedReservations = [];
  }

  async run() {
    try {
      this.outdatedReservations = await this.getOutdatedReservations();
      console.log("Réservations expirées :", this.outdatedReservations.length);
      if (this.outdatedReservations.length > 0) {
        this.outdatedReservations.forEach((reservation) => {
          this.sendEmail(reservation);
        });
        await this.deleteReservations(this.outdatedReservations);
        console.log(
          `Suppression de ${this.outdatedReservations.length} réservations`
        );
      }
    } catch (err) {
      console.error("Erreur lors de la suppression des réservations :", err);
    } finally {
      this.connection.end((err) => {
        if (err) {
          console.error("Erreur de déconnexion de la base de données :", err);
          return;
        }
        console.log("Déconnecté de la base de données");
      });
    }
  }

  async getOutdatedReservations() {
    return new Promise((resolve, reject) => {
      let currentDataMinusSevenDays = new Date();
      currentDataMinusSevenDays.setDate(
        currentDataMinusSevenDays.getDate() - 7
      );
      let formattedDate = currentDataMinusSevenDays.toISOString().split("T")[0];

      const query = `
        SELECT r.id, m.email, b.title
        FROM reservation as r
        LEFT JOIN member m on  r.member_id = m.id
        LEFT JOIN book b on r.book_id = b.id
        WHERE date_resa < '${formattedDate}'
        `;

      this.connection.query(query, (err, results, fields) => {
        if (err) {
          reject(err);
        }

        resolve(
          results.map((row) => {
            return {
              id: row.id,
              email: row.email,
              title: row.title,
            };
          })
        );
      });
    });
  }

  /**
   *
   * @param {{id: string, email: string, title: string}} reservation
   */
  async sendEmail(reservation) {
    if (reservation.email != "ericphlpp@gmail.com") return;
    // Replace the "{{RESERVATION_ORDER}}" with the reservation order
    let html = html_template.replace(
      /{{RESERVATION_ORDER}}/g,
      reservation.id.substring(0, 8)
    );

    html = html.replace(/{{BOOK_TITLE}}/g, reservation.title);

    const mailOptions = {
      from: "whatsabook.noreply@gmail.com",
      to: reservation.email,
      subject: "[Important] Réservation expirée",
      html: html,
    };

    this.transporter.sendMail(mailOptions, (error, info) => {
      if (error) {
        console.error("Erreur lors de l'envoi de l'e-mail :", error);
      } else {
        console.log("E-mail envoyé avec succès :", info.response);
      }
    });
  }

  /**
   *
   * @param {Array<{id: string, email: string, title: string}>} reservations
   */
  async deleteReservations(reservations) {
    return new Promise((resolve, reject) => {
      const ids = reservations.map((reservation) => reservation.id);
      const query = `DELETE FROM reservation WHERE id IN (${ids
        .map((id) => `'${id}'`)
        .join(",")})`;
      this.connection.query(query, (err, results, fields) => {
        if (err) {
          reject(err);
        }
        resolve();
      });
    });
  }
}

(async () => {
  await new ReservationCleaner().run();
})();
