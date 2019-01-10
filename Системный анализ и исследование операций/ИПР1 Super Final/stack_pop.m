function stack_pop = stack_pop(stack)
    if isstruct(stack)
        stack_pop = stack.value;
    else
        stack_pop = false;
    end;
end