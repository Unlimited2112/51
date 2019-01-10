using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.ComponentModel.DataAnnotations;

namespace ForumMVC.Models
{
    // Основная модель для БД
    public class Message
    {
        public int Id { get; set; }
        [Required]
        public string Content { get; set; }
        public int? TopicId { get; set; }
        public virtual Topic Topic { get; set; }
        public int? UserId { get; set; }
        public virtual User User { get; set; }
        public bool Disabled { get; set; }
        public DateTime Dates { get; set; }
    }
    // Вспомогательная модель для редактирования
    public class MessageEdit
    {
        public int Id { get; set; }
        [Required]
        public int TopicId { get; set; }
        [Display(Name = "Текст сообщения")]
        public string Content { get; set; }
        public bool Disabled { get; set; }
    }
}