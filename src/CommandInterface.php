<?php
namespace PhpDDD\Command;

/**
 * In Command-Query-Responsibility-Segregation (CQRS) a command is an order to modify the domain.
 * This command is passed to a CommandHandler responsible of interact with the domain model.
 *
 * A command should only consist of scalar values.
 * If, for example, you want to modify the title of a Post. It's the responsibility of the handler to get the post related.
 * The command will just contain the identifier of the post and the new title.
 *
 * In certain case, we may need to pass object to the Command.
 * In the example above, if we need to know which user is modifying the title and that user is not known in the Post's
 * bounded context (for instance, the owner of a Post is a PostOwnerInterface and the real user object implements it).
 * In that case, we may inject a User into the Command.
 * Another way to handle this is to delegate this job to the CommandHandler. It depends on your architecture.
 */
interface CommandInterface
{
}
