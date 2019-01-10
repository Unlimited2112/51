using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.ComponentModel.DataAnnotations;

namespace ForumMVC.Models
{
    // Основная модель для БД
    public class Topic
    {
        public Topic()
        {
            Messages = new HashSet<Message>();
        }
        public int Id { get; set; }
        [Required]
        public string Name { get; set; }
        public int? RubricId { get; set; }
        public virtual Rubric Rubric { get; set; }
        public int? UserId { get; set; }
        public virtual User User { get; set; }
        [Required]
        public string Content { get; set; }
        public virtual ICollection<Message> Messages { get; set; }
    }
    // Вспомогательная модель для вывода в список тем
    public class TopicView
    {
        public int Id { get; set; }
        public string Name { get; set; }
        public string Content { get; set; }
        public string TopicStarter { get; set; }
        public string CntMsg { get; set; }
        public string LastWriter { get; set; }
        public string Dates { get; set; }
    }
    // Вспомогательная модель для редактирования
    public class TopicEdit
    {
        public int Id { get; set; }
        [Required]
        [Display(Name = "Наименование")]
        public string Name { get; set; }
        public int RubricId { get; set; }
        [Required]
        [Display(Name = "Страртовое сообщение")]
        public string Content { get; set; }
    }
}