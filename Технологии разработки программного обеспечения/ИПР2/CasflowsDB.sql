IF EXISTS (SELECT * FROM dbo.sysobjects WHERE id = object_id('[dbo].[FK_Cashflows_Directions]') AND OBJECTPROPERTY(id, 'IsForeignKey') = 1) 
ALTER TABLE [dbo].[Cashflows] DROP CONSTRAINT [FK_Cashflows_Directions]
;

IF EXISTS (SELECT * FROM dbo.sysobjects WHERE id = object_id('[dbo].[Cashflows]') AND OBJECTPROPERTY(id, 'IsUserTable') = 1) 
DROP TABLE [dbo].[Cashflows]
;

IF EXISTS (SELECT * FROM dbo.sysobjects WHERE id = object_id('[dbo].[Directions]') AND OBJECTPROPERTY(id, 'IsUserTable') = 1) 
DROP TABLE [dbo].[Directions]
;

CREATE TABLE [dbo].[Cashflows]
(
	[Id] int NOT NULL IDENTITY,
	[Description] nvarchar(25) NOT NULL,
	[Summa] float NOT NULL,
	[DirectionId] int NOT NULL
)
;

CREATE TABLE [dbo].[Directions]
(
	[Id] int NOT NULL IDENTITY,
	[Name] nvarchar(10) NOT NULL
)
;

CREATE INDEX [IXFK_Cashflows_Directions] 
 ON [dbo].[Cashflows] ([DirectionId] ASC)
;

ALTER TABLE [dbo].[Cashflows] 
 ADD CONSTRAINT [PK_Cashflows]
	PRIMARY KEY CLUSTERED ([Id])
;

ALTER TABLE [dbo].[Directions] 
 ADD CONSTRAINT [PK_Directions]
	PRIMARY KEY CLUSTERED ([Id])
;

ALTER TABLE [dbo].[Cashflows] ADD CONSTRAINT [FK_Cashflows_Directions]
	FOREIGN KEY ([DirectionId]) REFERENCES [dbo].[Directions] ([Id]) ON DELETE No Action ON UPDATE No Action
;
