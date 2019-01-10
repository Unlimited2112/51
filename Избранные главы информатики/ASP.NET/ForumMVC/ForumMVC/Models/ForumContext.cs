using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Data.Entity;

namespace ForumMVC.Models
{
    // Для доступа к данным используем Entity Framework
    public class ForumContext : DbContext
    {
        public ForumContext() : base("name=ForumContext")
        {
        }
        public virtual DbSet<Message> Messages { get; set; }
        public virtual DbSet<Rubric> Rubrics { get; set; }
        public virtual DbSet<Role> Roles { get; set; }
        public virtual DbSet<Topic> Topics { get; set; }
        public virtual DbSet<User> Users { get; set; }

        protected override void OnModelCreating(DbModelBuilder modelBuilder)
        {
            modelBuilder.Entity<Role>()
                .HasMany(e => e.Users)
                .WithRequired(e => e.Role)
                .HasForeignKey(e => e.RoleId)
                .WillCascadeOnDelete(false);
            modelBuilder.Entity<Rubric>()
                .HasMany(e => e.Topics)
                .WithRequired(e => e.Rubric)
                .HasForeignKey(e => e.RubricId)
                .WillCascadeOnDelete(false);
            modelBuilder.Entity<Topic>()
                .HasMany(e => e.Messages)
                .WithRequired(e => e.Topic)
                .HasForeignKey(e => e.TopicId)
                .WillCascadeOnDelete(false);
            modelBuilder.Entity<User>()
                .HasMany(e => e.Topics)
                .WithRequired(e => e.User)
                .HasForeignKey(e => e.UserId)
                .WillCascadeOnDelete(false);
            modelBuilder.Entity<User>()
                .HasMany(e => e.Messages)
                .WithRequired(e => e.User)
                .HasForeignKey(e => e.UserId)
                .WillCascadeOnDelete(false);

            base.OnModelCreating(modelBuilder);
        }
    }
}