using System;
using System.Collections.Generic;
using System.Drawing;
using System.Linq;
using System.Windows.Forms;

namespace GypoWineryKviz
{
    public partial class MainForm : Form
    {
        // A kérdések és válaszok tárolása
        private List<Question> questions;
        private int currentQuestionIndex = 0;
        private int score = 0;

        public MainForm()
        {
            InitializeComponent();
            LoadQuestions();
            DisplayQuestion();
        }

        // Kérdések és válaszok feltöltése
        private void LoadQuestions()
        {
            questions = new List<Question>
            {
                new Question("Hol alakult meg a GypoWinery?", new List<string> { "Csévharaszti régió", "Tokaji régió", "Villány", "Eger" }, 0),
                new Question("Mikor ültették el a GypoWinery első szőlőültetvényét?", new List<string> { "1985", "1990", "2000", "2005" }, 1),
                new Question("Milyen technológiai újítást vezetett be a GypoWinery 2005-ben?", new List<string> { "Organikus gazdálkodási módszerek", "Modern borászat-technológiai újítások", "Új szőlőültetvények elhelyezése", "Nemzetközi forgalmazás" }, 1),
                new Question("Mi a GypoWinery küldetése?", new List<string> { "A világ legnépszerűbb borát készíteni", "Bemutatni a Csévharaszti terroir egyedülálló ízvilágát", "Új technológiák bevezetése a borászatban", "Csak vörösborokra koncentrálni" }, 1),
                new Question("Mi a GypoWinery elköteleződése?", new List<string> { "Fenntarthatóság és a helyi közösségek támogatása", "Nemzetközi piacokra való terjeszkedés", "Csak hagyományos borászmódszerek alkalmazása", "Csak édes borok készítése" }, 0),
                new Question("Milyen jövőbeli tervei vannak a GypoWinerynek?", new List<string> { "Új helyszínek nyitása világszerte", "Új borfajták bevezetése és a borászat bővítése", "Csak vörösborokra koncentrálni", "Borok kizárólagos online értékesítése" }, 1),
                new Question("Melyik vörösborunk illik legjobban steakhez vagy grillezett húsokhoz?", new List<string> { "Csévharaszti Kékfrankos", "Csévharaszti Cabernet Sauvignon", "Csévharaszti Rosé", "Csévharaszti Olaszrizling" }, 0),
                new Question("Milyen hőmérsékleten kell tálalni a GypoWinery Csévharaszti Zöld Veltelinit?", new List<string> { "8-10°C", "10-12°C", "16-18°C", "18-20°C" }, 1),
                new Question("Melyik borunkban található eper és cseresznye ízvilág, és tökéletes a nyári fogásokhoz?", new List<string> { "Csévharaszti Cabernet Sauvignon", "Csévharaszti Rosé", "Csévharaszti Olaszrizling", "Csévharaszti Kékfrankos" }, 1),
                new Question("Mi az ideális hőmérséklet a Csévharaszti Jégbor tálalásához?", new List<string> { "6-8°C", "8-10°C", "16-18°C", "18-20°C" }, 0),
                new Question("Melyik idézet fejezi ki a bor élvezetének fontosságát?", new List<string> { "A legjobb módja, hogy élvezd egy pohár bort, ha megosztod egy barátoddal.", "Az élet túl rövid ahhoz, hogy rossz bort igyunk.", "A bor folyamatos bizonyítéka annak, hogy Isten szeret minket.", "A bor minden étkezést alkalmassá tesz." }, 1),
            };

            // Véletlenszerűen 5 kérdést választunk ki a 11-ből
            var random = new Random();
            questions = questions.OrderBy(q => random.Next()).Take(5).ToList();
        }

        // A kérdés megjelenítése
        private void DisplayQuestion()
        {
            if (currentQuestionIndex < questions.Count)
            {
                var question = questions[currentQuestionIndex];
                label1.Text = question.Text;
                radioButton1.Text = question.Options[0];
                radioButton2.Text = question.Options[1];
                radioButton3.Text = question.Options[2];
                radioButton4.Text = question.Options[3];

                // Alaphelyzetbe állítjuk a válaszlehetőségeket
                radioButton1.Checked = false;
                radioButton2.Checked = false;
                radioButton3.Checked = false;
                radioButton4.Checked = false;
            }
            else
            {
                // Kvíz vége, eredmény kiírása
                string resultMessage = $"A kvíz véget ért! Elért eredményed: {score} helyes válasz.";

                if (score == 5)
                {
                    resultMessage += "\nNyert egy ingyenes borkostolást!";
                }
                else if (score <= 3)
                {
                    resultMessage += "\nNyert 10% kedvezményt bármelyik borunkból!";
                }

                MessageBox.Show(resultMessage);
                this.Close();
            }
        }

        // A válasz ellenőrzése
        private void button1_Click(object sender, EventArgs e)
        {
            var selectedOption = GetSelectedOption();

            // Ha nincs kiválasztott válasz, hibaüzenet
            if (selectedOption == -1)
            {
                MessageBox.Show("Kérlek válassz egyet!");
                return;
            }

            // Helyes válasz esetén pontot adunk
            if (selectedOption == questions[currentQuestionIndex].CorrectAnswerIndex)
            {
                score++;
            }

            // Következő kérdés
            currentQuestionIndex++;
            DisplayQuestion();
        }

        // Kiválasztott válasz
        private int GetSelectedOption()
        {
            if (radioButton1.Checked) return 0;
            if (radioButton2.Checked) return 1;
            if (radioButton3.Checked) return 2;
            if (radioButton4.Checked) return 3;
            return -1; // Ha nincs kiválasztott válasz
        }   

    }

    // Kérdés osztálya
    public class Question
    {
        public string Text { get; }
        public List<string> Options { get; }
        public int CorrectAnswerIndex { get; }

        public Question(string text, List<string> options, int correctAnswerIndex)
        {
            Text = text;
            Options = options;
            CorrectAnswerIndex = correctAnswerIndex;
        }

    }
}
